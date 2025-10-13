type Props = {
    onFiles: (files: FileList) => void;
    multiple?: boolean;
  };
  
  export default function ImageUploader({ onFiles, multiple = true }: Props) {
    return (
      <div className="border border-dashed p-4 rounded bg-white">
        <label className="block text-sm mb-2">Imagens do veículo</label>
        <input
          type="file"
          accept="image/*"
          multiple={multiple}
          onChange={(e) => {
            if (e.target.files && e.target.files.length > 0) onFiles(e.target.files);
          }}
        />
        <p className="text-xs text-slate-500 mt-2">Formatos comuns, até 2MB cada (conforme validação da API).</p>
      </div>
    );
  }
  